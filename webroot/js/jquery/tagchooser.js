
(function($) { jQuery.fn.tagchooser = function(){

        var selectID = this.id;
        var leftID = selectID + '_left';
        var rightID = selectID + '_right';
        var chooserID = selectID + '_chooser';
        var theForm = $(this).parents('form');
        
        this.each(function(){
            
            // gathering some data
            var selectID = this.id;
            var leftID = selectID + '_left';
            var rightID = selectID + '_right';
            var chooserID = selectID + '_chooser';
            var theForm = $(this).parents('form');
            
            // double-click moves an item to the other list
            $('#' + leftID).dblclick(function(){
                    $('#' + selectID + '_add').click();
            });

            $('#' + rightID).dblclick(function(){
                    $('#' + selectID + '_del').click();
            });

            // add/remove buttons
            $('#' + selectID + '_add').click(function(){
                      var left = $('#' + leftID);
                      var leftOpts = $('#' + leftID + ' option:selected');
                      var right = $('#' + rightID);
                      right.append(leftOpts);
                      sortBoxes(left.attr('id'), right.attr('id'));	
            });

            $('#' + selectID + '_del').click(function(){
                      var left = $('#' + leftID);
                      var rightOpts = $('#' + rightID + ' option:selected');
                      var right = $('#' + rightID);
                      left.append(rightOpts);
                      sortBoxes(left.attr('id'), right.attr('id'));
            });
            
            $('#' + selectID + '_addnew').click(function(){
                      var tag = $('#' + selectID + '_new').val();
                      var id = tag.toLowerCase().split(' ').join('');
                      if (tag != '') {
                          if ($('#id_' + tag).length == 0) {
                              var option = '<option value="new:' + tag + '" id="id_' + id + '">'+ tag + '</option>';
                              $('#' + rightID).append(option);
                              $('#' + selectID).append(option);
                              sortBoxes(rightID, null);
                              $('#' + selectID + '_new').val('');
                          } 
                      }
            });
              
            // copy of the options from the original element
            var opts = $(this).children().clone();
            
            // add an ID to each option for the sorting plugin
            opts.each(function(){
                    $(this).attr('id', $(this).attr('value'));
                    });
            
            // find the combo box in the DOM and append
            // a copy of the options from the original
            // element to the left side
            theForm.find('#' + leftID).append(opts);
            
            // and put the selected one to the right
            $('#' + selectID + '_add').click();
            
            // initial sorting
            sortBoxes(leftID, rightID);
            
            // hide original element
            $(this).hide();
            $('#' + chooserID).show();
            
            // bind a submit event to the enclosing form...
            theForm.submit(function(){
                    $('#' + selectID).find('option').removeAttr('selected');
                    // ...which looks at each option element
                    // from the right side...
                    $('#' + rightID).find('option').each(function(){
                            // ...and selects the corresponding option
                            // from the original element
                            var v = $(this).attr('value');
                            $('#' + selectID).find('option[value="' + v + '"]').attr('selected','selected');
                            });

                    return true;
                    });
                    
              
              // sort the boxes and clear highlighted items
              function sortBoxes(leftID, rightID){
                      var ids = '#' + leftID;
                      if (rightID != null) { ids = ids + ', #' + rightID; }
                      var toSort = $(ids);					

                      toSort.find('option').selso({
                            type: 'alpha', 
                            extract: function(o){ return $(o).text().toLowerCase(); } 
                            });

                      // clear highlights
                      $('#' + leftID + ', #' + rightID).find('option:selected').removeAttr('selected');
              }
              
            });
        };
})(jQuery);