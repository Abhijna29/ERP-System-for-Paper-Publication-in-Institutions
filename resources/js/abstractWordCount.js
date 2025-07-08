function countWords(textareaID, displayID, limit = 250, errorID = null) {
    const textarea = document.getElementById(textareaID);
    const display = document.getElementById(displayID);
    const errorDisplay = errorID ? document.getElementById(errorID) : null;

    textarea.addEventListener("input", () => {
        const words = textarea.value
            .trim()
            .split(/\s+/)
            .filter((w) => w.length > 0);

        display.innerText = `${words.length} / ${limit} words`;

        if (errorDisplay) {
            if (words.length > limit) {
                errorDisplay.innerText = `Abstract cannot exceed ${limit} words.`;
                errorDisplay.classList.remove("d-none");
            } else {
                errorDisplay.innerText = "";
                errorDisplay.classList.add("d-none");
            }
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    countWords("abstract", "wordCount", 250, "abstractError");
});
