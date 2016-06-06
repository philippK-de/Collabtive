/*
* Accordion slides for dom elements
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

    //slide speed
    this.slideDuration = 500;
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
* This method finds the visual toggles and content slides in the root element
 */
accordion2.prototype.initializeToggles = function () {
    //get accordion toggle - these are the visual arrows representing the toggle state
    this.accordionToggles = this.rootElement.querySelectorAll("." + this.classNames.toggle + ",." + this.classNames.toggleActive);
    //get accordion contents - these are the content areas representing the slides
    this.accordionContents = this.rootElement.querySelectorAll("." + this.classNames.content + ",." + this.classNames.contentActive);
}

/*
* Called in the constructor to enumerate the content slides and set their attributes
 */
accordion2.prototype.initializeAccordion = function () {
    //loop through the accordion content slides
    if (this.accordionContents.length > 0) {
        for (var i = 0; i < this.accordionContents.length; i++) {
            //enumerate the content slides
            //and hide their content and overflow
            this.accordionContents[i].dataset.slide = i;
            this.accordionContents[i].style.display = "none";
            this.accordionContents[i].style.overflow = "hidden";

            //set the ID of the content slide
            this.accordionContents[i].id = this.container + "_content" + i;
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
            Velocity(this.accordionContents[i],"slideDown",{
                duration: this.slideDuration,
                begin: this.showToggle()
            });
        }
        else {
          Velocity(this.accordionContents[i],"slideUp",{
                duration: this.slideDuration,
                complete: this.hideToggle()
            });
        }
    }

}
//this method is a legacy drop in for the old accordion / inner accordions
accordion2.prototype.activate = function (contentSlide) {
    this.initializeToggles();
    var numSlide = contentSlide.dataset.slide;

    console.log("slide " + numSlide + " toggled");
    for (var i = 0; i < this.accordionContents.length; i++) {
        //save the current content and toggle in an instance var so it can be used in other method scopes
        this.currentContent = this.accordionContents[i];
        this.currentToggle = this.accordionToggles[i];

        if (i == numSlide) {
            Velocity(this.accordionContents[i],"slideDown",{
                duration: this.slideDuration,
                begin: this.showSlide()
            });
        }
        else {
            Velocity(this.accordionContents[i],"slideUp",{
                duration: this.slideDuration,
                complete: this.hideSlide()
            });
        }
    }
}

accordion2.prototype.showSlide = function () {
    this.currentContent.className = this.classNames.contentActive;
    this.showToggle();
}
accordion2.prototype.hideSlide = function () {
    this.currentContent.className = this.classNames.content;
    this.hideToggle();
}
accordion2.prototype.showToggle = function () {
    this.currentToggle.className = this.classNames.toggleActive;
}
accordion2.prototype.hideToggle = function () {
    this.currentToggle.className = this.classNames.toggle;
}

