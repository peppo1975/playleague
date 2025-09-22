<?

	class TicketsController extends AppController {
			var $uses = Array('User','Group','Ticket','Post');
			var $name = "Tickets";
			var $login_required = true;
			var $helpers = array('Backend');
			
			function admin_index() {
			
			}
			
	}
