/**
 * E365 Grid Block - Editor JavaScript
 *
 * Syncs the number of e365-column child blocks with the columns ACF setting.
 * Enforces max column count and auto-creates columns when needed.
 */
(function () {
  if (typeof wp === "undefined" || !wp.hooks || !wp.data) {
    return;
  }

  const { select, dispatch, subscribe } = wp.data;

  // Track grids being processed to avoid loops
  const processingGrids = new Set();

  /**
   * Sync column blocks with column count setting
   */
  function syncGridColumns() {
    const { getBlocks, getBlock } = select("core/block-editor");
    const { updateBlockAttributes, insertBlock, removeBlock } =
      dispatch("core/block-editor");

    if (!getBlocks) return;

    const allBlocks = getBlocks();

    // Find all e365-grid blocks recursively
    function findGridBlocks(blocks, parent = null) {
      let grids = [];
      for (const block of blocks) {
        if (block.name === "acf/e365-grid") {
          grids.push({ block, parent });
        }
        if (block.innerBlocks && block.innerBlocks.length > 0) {
          grids = grids.concat(findGridBlocks(block.innerBlocks, block));
        }
      }
      return grids;
    }

    const gridBlocks = findGridBlocks(allBlocks);

    for (const { block: gridBlock } of gridBlocks) {
      // Skip if already processing this grid
      if (processingGrids.has(gridBlock.clientId)) {
        continue;
      }

      // Get column count from ACF data
      const acfData = gridBlock.attributes?.data || {};
      const targetColumns = parseInt(acfData.columns || 2, 10);

      // Get current e365-column children
      const columnBlocks = (gridBlock.innerBlocks || []).filter(
        (b) => b.name === "acf/e365-column"
      );
      const currentCount = columnBlocks.length;

      // If counts match, nothing to do
      if (currentCount === targetColumns) {
        continue;
      }

      // Mark as processing
      processingGrids.add(gridBlock.clientId);

      // Get ratio and reverse settings
      const ratio = acfData.column_ratio || "50-50";
      const reverse = acfData.reverse_on_mobile === "1";

      // Need to add columns
      if (currentCount < targetColumns) {
        const columnsToAdd = targetColumns - currentCount;
        for (let i = 0; i < columnsToAdd; i++) {
          const colIndex = currentCount + i + 1;
          const widthClass = getColumnWidthClass(colIndex, targetColumns, ratio);
          const orderClass = getOrderClasses(reverse, colIndex, targetColumns);

          const newColumn = wp.blocks.createBlock("acf/e365-column", {
            data: {
              column_width_class: (widthClass + " " + orderClass).trim(),
            },
          });

          insertBlock(newColumn, currentCount + i, gridBlock.clientId, false);
        }
      }
      // Need to remove columns (remove from end)
      else if (currentCount > targetColumns) {
        const columnsToRemove = currentCount - targetColumns;
        for (let i = 0; i < columnsToRemove; i++) {
          const blockToRemove = columnBlocks[currentCount - 1 - i];
          if (blockToRemove) {
            removeBlock(blockToRemove.clientId, false);
          }
        }
      }

      // Update width classes for all columns
      setTimeout(() => {
        const updatedGrid = getBlock(gridBlock.clientId);
        if (updatedGrid) {
          (updatedGrid.innerBlocks || []).forEach((col, idx) => {
            if (col.name === "acf/e365-column") {
              const colIndex = idx + 1;
              const widthClass = getColumnWidthClass(
                colIndex,
                targetColumns,
                ratio
              );
              const orderClass = getOrderClasses(
                reverse,
                colIndex,
                targetColumns
              );
              updateBlockAttributes(col.clientId, {
                data: {
                  ...col.attributes.data,
                  column_width_class: (widthClass + " " + orderClass).trim(),
                },
              });
            }
          });
        }
        processingGrids.delete(gridBlock.clientId);
      }, 100);
    }
  }

  /**
   * Get Tailwind column width class
   */
  function getColumnWidthClass(columnIndex, totalCols, ratio) {
    const base = "w-full";

    if (totalCols === 1) return base;

    if (totalCols >= 3) {
      const widthMap = { 3: "lg:w-1/3", 4: "lg:w-1/4" };
      return base + " " + (widthMap[totalCols] || "lg:w-1/4");
    }

    // 2-column layouts
    const ratioMap = {
      "50-50": ["lg:flex-1", "lg:flex-1"],
      "60-40": ["lg:w-3/5", "lg:w-2/5"],
      "40-60": ["lg:w-2/5", "lg:w-3/5"],
      "70-30": ["lg:w-[calc(70%-1rem)]", "lg:w-[calc(30%-1rem)]"],
      "30-70": ["lg:w-[calc(30%-1rem)]", "lg:w-[calc(70%-1rem)]"],
      "66-33": ["lg:w-2/3", "lg:w-1/3"],
      "33-66": ["lg:w-1/3", "lg:w-2/3"],
    };

    const widths = ratioMap[ratio] || ratioMap["50-50"];
    return base + " " + (widths[columnIndex - 1] || "lg:flex-1");
  }

  /**
   * Get order classes for mobile reordering
   */
  function getOrderClasses(reverse, index, total) {
    if (!reverse || total < 2) return "";

    if (total === 2) {
      if (index === 1) {
        return "order-2 lg:order-1";
      } else {
        return "order-1 lg:order-2";
      }
    }
    return "";
  }

  // Subscribe to store changes
  let debounceTimer;
  subscribe(() => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(syncGridColumns, 250);
  });

  // Initial sync after a short delay
  setTimeout(syncGridColumns, 500);
})();
