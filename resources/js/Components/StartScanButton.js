import * as axios from "axios";
const wrapper = "start-chrome-driver-scan-wrapper";
const button = "start-scan-active-id";
const inner = "start-chrome-driver-scan-level-2-wrapper";
const innerInside = "start-chrome-driver-scan-level-2-inner";
const powerButtonSide = "power-button-sides";
const runningTextClass = "home-chrome-scanning-info-section";
const resultsBlock = "scan-results-block";

document.getElementById(wrapper).addEventListener('click', event => {
    if (!isActive(wrapper)) {
        toggleState();
        axios.get('/start-scan').then(response => {
            if (response.status === 200){
                toggleState();
            }
        }).catch(error => {
            console.error(error.response);
            toggleState();
        })
    }
});

function toggleState() {
    toggleActive(wrapper);
    toggleActive(button);
    toggleActive(inner);
    toggleActive(innerInside);
    toggleActive(powerButtonSide);

    setTimeout(() => {
        const entireBox = document.getElementById(wrapper);
        if (isActive(wrapper)) {
            entireBox.style.transform = "translateY(150px)";
        } else {
            entireBox.style.transform = "";
        }
    }, 1100);

    if (isActive(runningTextClass)) {
        toggleActive(runningTextClass);
        toggleActive(button, "spinning");

        // Show Results
        setTimeout(() => {
            toggleActive(resultsBlock);
        }, 2300);
    } else {
        if (isActive(resultsBlock)) {
            toggleActive(resultsBlock);
        }
        setTimeout(() => {
            toggleActive(runningTextClass);
            toggleActive(button, "spinning");
        }, 2300);
    }
}

function toggleActive(id, className='active') {
    const element = document.getElementById(id);
    if (isActive(id, className)) {
        element.classList.remove(className);
    } else {
        element.classList.add(className);
    }
}

function isActive(id, className="active") {
    const element = document.getElementById(id);
    return element.classList.contains(className);
}
