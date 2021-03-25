function showModal(x) {
			document.getElementById(x).style.visibility = "visible";
			document.getElementById(x).style.opacity = "1";
			document.getElementById("act").style.visibility = "visible";
			document.getElementById("act").style.opacity = "1";
}
function hideModal() {
			document.getElementById("pw").style.visibility = "hidden";
			document.getElementById("pw").style.opacity = "0";
			document.getElementById("act").style.visibility = "hidden";
			document.getElementById("act").style.opacity = "0";
			document.getElementById("popupcontentEDIT").innerHTML = 'Загрузка...<br/> <img src="img/loading.gif" align=middle /> <br/>';
			document.getElementById("popupcontentDELETE").innerHTML = 'Загрузка...<br/> <img src="img/loading.gif" align=middle /> <br/>';
			document.getElementById("popupcontentADD").innerHTML = 'Загрузка...<br/> <img src="img/loading.gif" align=middle /> <br/>';
}