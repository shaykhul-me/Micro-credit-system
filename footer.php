	<!-- Image Lightbox Modal -->
	<div id="image_show-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div id="image_modal_dialog" class="modal-dialog modal-lg" role="document">
			<div class="modal-content" id="image_modal_content">
				<div class="modal-body text-center">
					<button class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true" class="text-white">&times;</span>
					</button>
					<img src="" alt="" srcset="" id="image_show">
				</div>
			</div>
		</div>
	</div>

	<!-- </div> -->
	<!-- END PAGE CONTENT-->
	<!-- <footer class="page-footer">
    <div class="font-13">2018 © <b>AdminCAST</b> - All rights reserved.</div>
    <a class="px-4" href="http://themeforest.net/item/adminca-responsive-bootstrap-4-3-angular-4-admin-dashboard-template/20912589" target="_blank">BUY PREMIUM</a>
    <div class="to-top"><i class="fa fa-angle-double-up"></i></div>
</footer>-->
	</div>
	</div>
	<script>
		$(document).ready(function() {
			$('#sidebar-collapse').slimScroll({
				height: '100%',
				railOpacity: '0.9',
			});
			$(".datepicker").datepicker({
				changeMonth: true,
				changeYear: true
			});
		});
	</script>
	<!-- CORE PLUGINS-->
	<!-- <script src="assets/js/jquery.min.js" type="text/javascript"></script> -->
	<script src="assets/js/popper.min.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="assets/js/metisMenu.js" type="text/javascript"></script>
	<script src="assets/js/jquery.slimscroll.min.js" type="text/javascript"></script>
	<!-- PAGE LEVEL PLUGINS-->
	<script src="assets/js/chart/Chart.min.js" type="text/javascript"></script>
	<script src="assets/js/jquery-jvectormap-2.0.3.min.js" type="text/javascript"></script>
	<script src="assets/js/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
	<script src="assets/js/jquery-jvectormap-us-aea-en.js" type="text/javascript"></script>
	<!-- CORE SCRIPTS-->
	<script src="assets/js/app.min.js" type="text/javascript"></script>
	<!-- PAGE LEVEL SCRIPTS-->
	<!-- <script src="assets/js/scripts/dashboard_1_demo.js" type="text/javascript"></script> -->


	<!-- Page Refresh script -->
	<script type="text/javascript">
		// const form = document.getElementById('myForm');
		$(document).ready(function() {
			window.onload = function() {
				// Reset the form when the page loads
				// $("form").each(function() {
				//     if($(this).hasClass('ignore_input')){
				// 		return
				// 	}
				//     var not_reset_status = $(this).find("#not_reset_form").val()||false;
				// 	if(not_reset_status){
				// 		return;
				// 	}
				// 	$(this)[0].reset();
				// 	$(this).find('input:not([type="hidden"]):not([type="submit"]):not([type="button"]):not([readonly]), textarea, select').val('');
				// })
				// $('#myForm')[0].reset();
				// $('#myForm').find('input:not([type="hidden"]), textarea, select').val('')
			}
		});


		// Define the modal HTML as a string
		// Handle form submission
		//document.getElementById('myForm').addEventListener('submit', );
		function submission(redirecturl, title, msg) {
			// const form = document.getElementById('myForm');

			// Check if the form is valid
			// if (!form.checkValidity()) {
			//   form.reportValidity(); // Display validation errors
			//   return;
			// }

			// Define the modal HTML as a string
			
			const modalHTML = `<div class="modal" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="successModalLabel">` + title + `</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">` + msg + `
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									</div>
									</div>
								</div>
								</div>`;

			// Append the modal HTML to the body
			document.body.insertAdjacentHTML('beforeend', modalHTML);

			// Show the success modal
			const successModal = new bootstrap.Modal(document.getElementById('successModal'));
			successModal.show();

			// Redirect after the modal closes
			// document.getElementById('successModal').addEventListener('hidden.bs.modal', function() {
			// 	if (redirecturl != "") {
			// 		window.location.href = "" + redirecturl; // Replace with your redirect URL
			// 	}
			// });
			$(document).on("hidden.bs.modal", "#successModal", function (){
				if (redirecturl != "") {
					window.location.href = "" + redirecturl; // Replace with your redirect URL
				}
			})

			// Optionally, reset the form
			// form.reset();
		}
		
		<?php
		echo isset($form_success_message) ? $form_success_message : "";
		?>
	</script>
	</body>

	</html>