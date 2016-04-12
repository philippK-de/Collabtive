/**
 * Created by philipp on 12.04.2016.
 */

function accordion2(container, options) {
    if (options === undefined) {
        this.classNames = {
            toggle: "accordion_toggle",
            toggleActive: "accordion_toggle_active",
            content: "accordion_content"
        }
    }
    else {
        this.classNames = options.classNames;
    }
    this.container = container;
    this.rootElement = document.getElementById(this.container);
    this.blockToggles = this.rootElement.querySelectorAll("a.win_block, a.win_none");

    this.initializeToggles();
    this.initializeAccordion();

}

accordion2.prototype.initializeToggles = function () {
    this.accordionArrows = this.rootElement.querySelectorAll("." + this.classNames.toggleActive);
    this.accordionToggles = this.rootElement.querySelectorAll("." + this.classNames.toggle);
    this.accordionContents = this.rootElement.querySelectorAll("." + this.classNames.content);
}
accordion2.prototype.initializeAccordion = function () {
    if (this.accordionToggles.length > 0 && this.accordionContents.length > 0) {
        for (var i = 0; i < this.accordionToggles.length; i++) {
            this.accordionToggles[i].dataset.slide = i;
            this.accordionToggles[i].id = this.container + "toggle" + i;
            this.accordionToggles[i].addEventListener("click", this.toggle);


            this.accordionContents[i].style.display = "none";
            this.accordionContents[i].id = this.container + "content" + i;
        }
    }
}
accordion2.prototype.toggle = function (toggle) {
    var numSlide = toggle.dataset.slide;

    for (var i = 0; i < this.accordionContents.length; i++) {
        if (i == numSlide) {
            this.blockToggles[i].className = "win_block";
            Effect.BlindDown(this.accordionContents[i].id);
        }
        else {

            this.blockToggles[i].className = "win_none";
            Effect.BlindUp(this.accordionContents[i].id);
        }

    }

}
accordion2.prototype.activate = function (toggle) {
    var numSlide = toggle.dataset.slide;
    this.initializeToggles();

    for (var i = 0; i < this.accordionContents.length; i++) {
        this.accordionContents[i].id = this.container + "content" + i;

        console.log(this.accordionContents[i].id);
        if (i == numSlide) {

            this.accordionContents[i].className = "accordion_content blind-content in origin-top";
            this.accordionContents[i].style.display = "block";

        }
        else {
            // Effect.BlindUp(this.accordionContents[i].id);

            this.accordionContents[i].className = "accordion_content blind-content out origin-top";
            this.accordionContents[i].style.display = "none";

        }

    }
    console.log(toggle);

}
