<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>realforeclose data scraper V1.0</title>

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

	
	
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/gijgo.min.css" rel="stylesheet" type="text/css" />
	<style>
		.error {
			border: 5px solid red !important;
		}
		
		.download-link {
			color: #26c605;
		}
		
		.download-error {
			color: #f20404;
		}
		
	</style>
  </head>
  <body>

    <div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
		</div>
		<div class="col-md-4">
			<form id="county-form" role="form" autocomplete="off">
				<div class="form-group">
					 
					<label for="courtHouse">
						Choose an action to scrap:
					</label>
					<select id="courtHouse" name="get-data" class="form-control">
						<option value="none"></option>
						<option value="all" selected="selected">Scrap.</option>
						<option value="force">Force scrap.</option>
					</select>
				</div>
				
				<button id="scrape-button" type="button" class="btn btn-primary">
					Scrap now
				</button>
				<p id="result"></p>
			</form>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/gijgo.min.js" type="text/javascript"></script>
    <script src="js/scripts.js"></script>
	
	<script>
		
		$(document).ready(function() {
			let toDay = Date.now();
			let result_label = $("p#result");

			$("button#scrape-button").on('click', function(e){
				e.preventDefault();
				
				result_label.removeClass('download-error').html('');
				
				if($('#courtHouse option:selected').text() === '') {
					$("#courtHouse").css("border", "2px solid red");
					$("#courtHouse").focus();
					return;
				}
				
				
				$("#courtHouse").css("border", "0.916667px solid rgba(0, 0, 0, 0.15)");
				$("#courtHouse").focus();
				
				
				
				result_label.html('<b>Please don\'t close the browser until the link appears here.</b>')
				const formData = JSON.stringify({ 
								  'court': $('#courtHouse option:selected').val(), 
								  'startDate': $('#startDate').val(), 
								  'endDate': $('#endDate').val()
								});
							
				const formData2 = $("#county-form").serialize();
                let last_response = '';
				$.ajax({
					//url: "testAJAX.php",
					url: "AjaxThread.php",
					method: 'POST',
					data: formData2,
					dataType: 'text',
					//contentType: 'application/json',
					encode: true,
                    xhrFields: {
                        onprogress: function(e) {
                            let response = e.target.response.replace(/\n/g, '').split("|").slice(0, -1).pop();
                            //console.log('response split: ', e.target.response.replace(/\n/g, '').split("|").slice(0, -1));
                            console.log('response pop: ', response);
                            //console.log('response pop: ', JSON.parse(response).replace(/\n/g, ''));
                        }
                    },
					success: function (result, status, xhr) {
						console.log('success step');
						console.log(result.status);
						if(result.status === 'success') {
							result_label.html(result.data);
						}
						else {
							result_label.removeClass('download-link').addClass('download-error').html(result.data);
						}
					},
					error: function(request, status, error) {
						result_label.removeClass('download-link').addClass('download-error').html('Internal error, please contact the support.');
						console.log('error step');
						//console.log(JSON.stringify(request));
						console.log(request);
						console.log(status);
						console.log(error);
					}
				});
				
				
			});
		
		
		
			function padTo2Digits(num) {
			  return num.toString().padStart(2, '0');
			}

			function formatStartDate(date) {
			  return [
				padTo2Digits(date.getMonth() + 1),
				padTo2Digits(01),
				date.getFullYear(),
			  ].join('/');
			}

			function formatEndDate(date) {
			  return [
				padTo2Digits(date.getMonth() + 1),
				padTo2Digits(date.getDate()),
				date.getFullYear(),
			  ].join('/');
			}
		});
    </script>
  </body>
</html>