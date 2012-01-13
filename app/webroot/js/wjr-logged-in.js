$(document).ready(function() {
     $('.content-block').editable('/contentBlocks/edit', { 
         type      : 'textarea',
         cancel    : 'Cancel',
         submit    : 'Save',
         loadurl   : '/contentBlocks/view',
         tooltip   : 'Click to edit…',
         onblur    : 'ignore'
     });
     
     $('.content-block').addClass('editable');
});
 
 $(document).ready(function() {
     $('.page-resource').editable('/pages/edit', { 
         type      : 'textarea',
         cancel    : 'Cancel',
         submit    : 'Save',
         loadurl   : '/pages',
         tooltip   : 'Click to edit…',
         onblur    : 'ignore'
     });
     
     $('.page-resource-title').editable('/pages/edit', { 
         type      : 'textarea',
         cancel    : 'Cancel',
         submit    : 'Save',
         loadurl   : '/pages',
         name      : 'name',
         loaddata  : { name: true },
         tooltip   : 'Click to edit…',
         onblur    : 'ignore'
     });
     
     $('.page-resource, .page-resource-title').addClass('editable');
});