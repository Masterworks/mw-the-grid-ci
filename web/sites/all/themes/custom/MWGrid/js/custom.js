jQuery.noConflict();

(function( $ ) {
$(function() 
{
    $( document ).ready(function() 
    {
	  	$(".tabs--primary").click(function()
	  	{
  			$(this).toggleClass("activated");
  		});
  		
  		$("#navbar").animate({"top" : $("#admin-menu-wrapper").outerHeight()}, 250);
  		$(".tabs--primary.nav").animate({"top" : $("#admin-menu-wrapper").outerHeight() + $("#navbar").outerHeight() + 10}, 250);


  		/***************************************FUNCTION CALLS**************************************/
  		tableOrganizer();
	});

	$( window ).resize(function() 
	{
		$("#navbar").animate({"top" : $("#admin-menu-wrapper").outerHeight()}, 250);
	  	$(".tabs--primary.nav").animate({"top" : $("#admin-menu-wrapper").outerHeight() + $("#navbar").outerHeight() + 10}, 250);
	
		/***************************************FUNCTION CALLS**************************************/
	  	tableOrganizer();
	});

	function tableOrganizer() /*Reorganizes tables for mobile*/
	{
		var $viewsTable;
		
		for (i=0;i<$(".views-table").length; i++)
		{
			//$viewsTable = $(".views-table").get(i);

			if ($(window).width() <= 600)
			{
				var tdArray = $($($(".views-table").get(i)).find("tbody tr td")).toArray();
				$(".views-table tbody").remove();
				
				for (j=0;j<$(".views-table thead tr th").length; j++)
				{
					$($($(".views-table thead tr th").get(j)).css({"display":"inline-block", "width":"50%", "vertical-align":"top"})).after($(tdArray[j]).css({"display":"inline-block", "width":"50%", "vertical-align":"top"}));
				}	
			}
			else
			{
				console.log($viewsTable);
				//$($(".views-table").get(i)).html($viewsTable);
			}
					
		}
		
	}

  });
})(jQuery);