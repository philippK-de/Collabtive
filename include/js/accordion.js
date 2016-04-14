/**
 * Created by philipp on 12.04.2016.
 */

function accordion2(container, options) {
    //set defaults for CSS class names
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
    //setup base elements
    this.container = container;
    this.rootElement = document.getElementById(this.container);

    //instance variable to hold the currently handled slide and toggle
    this.currentContent = {};
    this.currentToggle = {};

    this.initializeToggles();
    this.initializeAccordion();

}

/*
 * initialize toggle and content lists
 */
accordion2.prototype.initializeToggles = function () {
    //get accordion toggle - these are the visual arrows representing the toggle state
    this.accordionToggles = this.rootElement.querySelectorAll("." + this.classNames.toggle + ",." + this.classNames.toggleActive);
    //get accordion contents - these are the content areas representing the slides
    this.accordionContents = this.rootElement.querySelectorAll("." + this.classNames.content + ",." + this.classNames.contentActive);
}

/*
 * loop over contents and set some properties
 */
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

// this method is used for block accordions and new accordions
accordion2.prototype.toggle = function (contentSlide) {
    //get number of the slide to be opened
    var numSlide = contentSlide.dataset.slide;

    for (var i = 0; i < this.accordionContents.length; i++) {
        //save the current content and toggle in an instance var so it can be used in other method scopes
        this.currentContent = this.accordionContents[i];
        this.currentToggle = this.accordionToggles[i];

        if (i == numSlide) {
            Effect.BlindDown(this.accordionContents[i].id, {
                afterFinish: this.showToggle()
            });
        }
        else {
            this.accordionToggles[i].className = this.classNames.toggle;
            Effect.BlindUp(this.accordionContents[i].id,{
                afterFinish: this.hideToggle()
            });
        }
    }

}
//this method is a legacy drop in for the old accordion / inner accordions
accordion2.prototype.activate = function (contentSlide) {
    this.initializeToggles();
    var numSlide = contentSlide.dataset.slide;

    for (var i = 0; i < this.accordionContents.length; i++) {
        //save the current content and toggle in an instance var so it can be used in other method scopes
        this.currentContent = this.accordionContents[i];
        this.currentToggle = this.accordionToggles[i];

        if (i == numSlide) {
            Effect.BlindDown(this.accordionContents[i].id, {
                duration: 0.4,
                beforeStart: this.showSlide()
            });
        }
        else {
            Effect.BlindUp(this.accordionContents[i].id, {
                duration: 0.4,
                afterFinish: this.hideToggle()
            });
        }
    }
}

accordion2.prototype.showSlide = function () {
    this.currentContent.className = this.classNames.contentActive;
    this.currentToggle.className = this.classNames.toggleActive;
}
accordion2.prototype.showToggle = function () {
    this.currentToggle.className = this.classNames.toggleActive;
}
accordion2.prototype.hideToggle = function () {
    this.currentToggle.className = this.classNames.toggle;
}

