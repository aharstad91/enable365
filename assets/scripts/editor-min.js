<?php
wp.domReady( () => {

	 wp.blocks.registerBlockStyle(
		  'core/spacer',
		  [
			   {
					name: 'default',
					label: 'Default',
					isDefault: true,
			   },
			   {
					name: 'small',
					label: 'S',
			   },
			   {
					name: 'medium',
					label: 'M',
			   },
			   {
					name: 'large',
					label: 'L',
			   },
			   {
					name: 'x-large',
					label: 'XL',
			   }
		  ]
	 );
	
} );