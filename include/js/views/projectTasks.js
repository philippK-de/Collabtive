
var accord_dashboard = new accordion2('blockTasks',{
    classNames: {
        toggle: 'win_none',
            toggleActive: 'win_block',
            content: 'blockaccordion_content'
    }
});

function activateAccordeon(theAccord){
    accord_dashboard.toggle($$('#blockTasks .blockaccordion_content')[theAccord]);
    setCookie("activeSlideProject",theAccord);
}

//var theBlocks = $$("#block_dashboard > div .headline > a");
var theBlocks = document.querySelectorAll("#blockTasks > div[class~='headline'] > a");

//loop through the blocks and add the accordion toggle link
openSlide = 0;
for(i=0;i<theBlocks.length;i++)
{
    theCook = readCookie("activeSlideProject");
    if(theCook > 0)
    {
        openSlide = theCook;
    }

    var theAction = theBlocks[i].getAttribute("onclick");
    theAction += "activateAccordeon("+i+");";
    theBlocks[i].setAttribute("onclick",theAction);
    //console.log(theBlocks[i].getAttribute("onclick"));
}


//accordIndex.activate($$('#block_index .acc_toggle')[0]);
activateAccordeon(0);