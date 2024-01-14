// jQuery(document).ready(function($) {
//     // Function to update color pickers dynamically
//     function updateColorPickers(initialColors) {
//         $('#primary_base_color').val(initialColors.primary).trigger('input');
//         $('#secondary_base_color').val(initialColors.secondary).trigger('input');
//         $('#tertiary_base_color').val(initialColors.tertiary).trigger('input');
//     }

//     // Call the function with initial colors
//     updateColorPickers(easyuiProInitialColors);

//     // Hook into the color picker change event
//     $('.wp-color-picker').on('input', function() {
//         // Update the color picker value to trigger the change event
//         $(this).val($(this).val()).trigger('change');
//     });
// });