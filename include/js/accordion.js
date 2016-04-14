/**
 * Created by philipp on 12.04.2016.
 */

function accordion2(container, options) {
    if (options === undefined) {
        this.classNames = {
            toggle: "acc-toggle",
            toggleActive: "acc-toggle-active",
            content: "accordion_content",
            contentActive: "accordion_content_active"
        }
    }
    else {
        this.classNames = options.classNames;
    }
    this.container = container;
    this.rootElement = document.getElementById(this.container);

    this.initializeToggles();
    this.initializeAccordion();

}

accordion2.prototype.initializeToggles = function () {
    this.accordionToggles = this.rootElement.querySelectorAll("." + this.classNames.toggle + ",." + this.classNames.toggleActive);
    this.accordionContents = this.rootElement.querySelectorAll("." + this.classNames.content + ",." + this.classNames.contentActive);
}
accordion2.prototype.initializeAccordion = function () {
    if (this.accordionContents.length > 0) {
        for (var i = 0; i < this.accordionContents.length; i++) {
            this.accordionContents[i].dataset.slide = i;
            this.accordionContents[i].style.display = "none";
            this.accordionContents[i].style.overflow = "hidden";

            this.accordionContents[i].id = this.container + "content" + i;
        }
    }
}
accordion2.prototype.toggle = function (contentSlide) {
    var numSlide = contentSlide.dataset.slide;

    for (var i = 0; i < this.accordionContents.length; i++) {
        if (i == numSlide) {
            this.accordionToggles[i].className = this.classNames.toggleActive;
            Effect.BlindDown(this.accordionContents[i].id);
        }
        else {
            this.accordionToggles[i].className = this.classNames.toggle;
            Effect.BlindUp(this.accordionContents[i].id);
        }
    }

}
accordion2.prototype.activate = function (contentSlide) {
    this.initializeToggles();
    var numSlide = contentSlide.dataset.slide;

    for (var i = 0; i < this.accordionContents.length; i++) {
        if (i == numSlide) {
            //this.accordionContents[i].className = "accordion_content blind-content in origin-top";
            this.accordionContents[i].className = this.classNames.contentActive;
            this.accordionToggles[i].className = this.classNames.toggleActive;

             Effect.BlindDown(this.accordionContents[i].id);
        }
        else {
            this.accordionContents[i].className = this.classNames.content;
            this.accordionToggles[i].className = this.classNames.toggle;
        }

    }

}
