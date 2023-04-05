// CONTROLE SIDEBAR
function esconderSideBar() {

	const nomeClasseBody = document.getElementById('idBody').className;
	const el = document.getElementById('idBody');

	if (nomeClasseBody == 'app sidebar-mini') {

		el.classList.remove('app', 'sidebar-mini');
		el.classList.add('app', 'sidebar-mini', 'sidenav-toggled');

	} else {
		el.classList.remove('app', 'sidebar-mini', 'sidenav-toggled');
		el.classList.add('app', 'sidebar-mini');

	}

}
// CONTROLE SIDEBAR
