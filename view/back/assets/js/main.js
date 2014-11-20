jQuery(function($){
    anwserCount = 1;
    $('.selectpicker').selectpicker();
    $('.textareahtml').wysihtml5();
    $('.datepicker').datepicker({autoclose: true})
    $("#start-publish-time").mask("99:99:99");
    $("#end-publish-time").mask("99:99:99");
    $(".icheck").iCheck({
        checkboxClass: 'icheckbox_square-grey',
        radioClass: 'iradio_square-grey'
    });

    $(".sortable").sortable({
        placeholder: 'list-group-item list-group-item-placeholder',
        forcePlaceholderSize: true,
        update : function(event, ui){
            var item = $(ui.item);
            var url = '/admin/playgroundcms/blocklayoutzone/update/'+$('#layoutId').attr('value')+'/'+item.attr("data-block-layout-zone")+'/'+item.index();
            $.get(url);
        }
    });
    
    $('#tabZone li a').each(function(index, elt){
        $(elt).click(function(){
            $('#layoutZone').attr('value', $(elt).attr('data-zone-id'));
        })
    });

    $('#layoutZone').attr('value', $($('#tabZone li a')[0]).attr('data-zone-id'));

    $('.blockType').change(function(){
        var block =  $(this).val().split("\\");
        var blockName = block[block.length - 1];
        window.location.href = "/admin/playgroundcms/block/create/"+blockName;
    });

    $('.newBlock').change(function(){
        var block =  $(this).val().split("\\");
        var blockName = block[block.length - 1];
        
        $.get("/admin/playgroundcms/block/createwithoutlayout/"+blockName, function( data ) {
          $("#contentForm").html(data);
          $('#formBlock').modal();
          $('#layoutZoneId').attr('value', $('#layoutZone').attr('value'));
        })
    });

    $('#addAnswer').click(function(){
        var klon = $('#answerfr_FR .answer1');
        klon.clone().attr('class', 'control-group answer'+(++anwserCount)).show().insertAfter(klon);

        var klon = $('#answeren_US .answer1');
        klon.clone().attr('class', 'control-group answer'+(++anwserCount)).show().insertAfter(klon);
    })

    $('#nestableMenu').nestable({
        group: 1,
        expandBtnHTML : '',
        collapseBtnHTML : '',
    })
    .on('change', function(e){
        var array = [ ];
        $('#nestableMenu').find(".dd-item").each(function(key, element){
            
            if($($($(element).parent()[0]).prev()).hasClass('dd-actions')) {
                var parentId = $($(element).parent().parent()).attr("data-id");
                var id = "children-"+parentId+"-"+$(element).attr("data-id");
            }else if ($($($(element).parent()[0]).prev()).hasClass('dd-item')) {
                var parentId = $($(element).parent().prev()).attr("data-id");
                var id = "children-"+parentId+"-"+$(element).attr("data-id");
                if($($($(element).parent()[0]).prev()).hasClass('isRoot')){
                    var id = $(element).attr("data-id");
                }
            }else {
                var id = $(element).attr("data-id");
            }
            array.push(id);
        })
        $.post("/admin/playgroundcms/menu/position",{'data' : window.JSON.stringify(array)});
    });

    $('.subMenuCollapse').click(function(element){
        $(this).next().show();
        $(this).hide();
        $(this).parent().next().hide();
    })

    $('.subMenuExpand').click(function(element){
        $(this).prev().show();
        $(this).hide();
        $(this).parent().next().show();     
    })

    if($('#searchHightlight').length > 0){
        $(".tab-content").highlight($('#searchHightlight').html());
    }
});