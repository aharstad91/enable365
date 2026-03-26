/**
 * E365 Grid Block - Editor JavaScript
 *
 * Syncs the number of e365-column child blocks with the columns ACF setting.
 * Enforces max column count and auto-creates columns when needed.
 *
 * Desktop layout (ratios, widths) is handled entirely by CSS data-attribute
 * selectors in editor.css — this script only manages column count.
 */
(function () {
  if (typeof wp === "undefined" || !wp.hooks || !wp.data) {
    return;
  }

  const { select, dispatch, subscribe } = wp.data;

  // Track grids currently being modified to prevent re-entrant processing
  const processingGrids = new Set();

  // Track last known column count per grid to detect actual changes
  const lastKnownCounts = new Map();

  /**
   * Find all e365-grid blocks recursively in the block tree
   */
  function findGridBlocks(blocks) {
    let grids = [];
    for (const block of blocks) {
      if (block.name === "acf/e365-grid") {
        grids.push(block);
      }
      if (block.innerBlocks && block.innerBlocks.length > 0) {
        grids = grids.concat(findGridBlocks(block.innerBlocks));
      }
    }
    return grids;
  }

  /**
   * Sync column blocks with column count setting
   */
  function syncGridColumns() {
    const { getBlocks } = select("core/block-editor");
    const { insertBlock, removeBlock } = dispatch("core/block-editor");

    if (!getBlocks) return;

    const gridBlocks = findGridBlocks(getBlocks());

    for (const gridBlock of gridBlocks) {
      // Skip if currently being modified
      if (processingGrids.has(gridBlock.clientId)) {
        continue;
      }

      // Get column count from ACF data, clamp to valid range 1-4
      const acfData = gridBlock.attributes?.data || {};
      const rawColumns = parseInt(acfData.columns || "2", 10);
      const targetColumns = Math.max(
        1,
        Math.min(4, isNaN(rawColumns) ? 2 : rawColumns)
      );

      // Get current e365-column children
      const columnBlocks = (gridBlock.innerBlocks || []).filter(
        (b) => b.name === "acf/e365-column"
      );
      const currentCount = columnBlocks.length;

      // If counts match, nothing to do
      if (currentCount === targetColumns) {
        lastKnownCounts.set(gridBlock.clientId, currentCount);
        continue;
      }

      // Mark as processing to prevent re-entrance from store updates
      processingGrids.add(gridBlock.clientId);

      try {
        if (currentCount < targetColumns) {
          // Add missing columns
          const columnsToAdd = targetColumns - currentCount;
          for (let i = 0; i < columnsToAdd; i++) {
            const newColumn = wp.blocks.createBlock("acf/e365-column", {
              data: {
                column_width_class: "w-full",
              },
            });
            if (newColumn) {
              insertBlock(
                newColumn,
                currentCount + i,
                gridBlock.clientId,
                false
              );
            }
          }
        } else if (currentCount > targetColumns) {
          // Remove excess columns from the end
          // Collect all clientIds BEFORE removing any to avoid stale indices
          const idsToRemove = [];
          for (let i = currentCount - 1; i >= targetColumns; i--) {
            if (columnBlocks[i]) {
              idsToRemove.push(columnBlocks[i].clientId);
            }
          }
          for (const clientId of idsToRemove) {
            removeBlock(clientId, false);
          }
        }

        lastKnownCounts.set(gridBlock.clientId, targetColumns);
      } catch (error) {
        console.error("E365 Grid: Error syncing columns", error);
      }

      // Clear processing flag after store settles
      setTimeout(() => {
        processingGrids.delete(gridBlock.clientId);
      }, 50);
    }
  }

  // Subscribe to store changes with debounce
  let debounceTimer;
  subscribe(() => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(syncGridColumns, 150);
  });

  // Initial sync after editor loads
  setTimeout(syncGridColumns, 500);
})();
