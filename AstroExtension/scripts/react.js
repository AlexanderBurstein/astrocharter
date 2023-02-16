if (typeof newText === 'string') {
    const spans = document.querySelectorAll("span");
    alert(data);
    for (let i = 0; i < spans.length; i++) {
	alert(spans[i].innerHTML);
	var datePart = spans[i].innerHTML.match(/(^0[1-9]|[12][0-9]|3[01]).(0[1-9]|1[0-2]).(\d{4}$)/);
	if (datePart != null && !!datePart.length) {spans[i].style.backgroundColor = "red";}
    }
}