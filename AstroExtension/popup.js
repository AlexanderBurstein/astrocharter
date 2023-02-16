$(document).ready(function() {
	document.getElementById("img_wheel").addEventListener("click", getData);
});

function reactOnData(data) {
    const divs = document.querySelectorAll("div");
    var names = data.split("\n");
    for (let i = 0; i < divs.length; i++) {
	if (names.indexOf(divs[i].innerHTML) > -1) {divs[i].style.backgroundColor = "palegreen";}
    }
}

function getData() {
    var textFrame = document.getElementById('ifNames').contentDocument;
    var textObject = textFrame.getElementsByTagName('pre')[0];
    var text = textObject.innerHTML;
    chrome.tabs.query({active: true}, (tabs) => {
        const tab = tabs[0];
        if (tab) {
		chrome.scripting.executeScript({
			args: [text],
			target: {tabId: tab.id, allFrames: true},
        		func: reactOnData
		});
	} else {
            	alert("There are no active tabs");
        }
    });
}