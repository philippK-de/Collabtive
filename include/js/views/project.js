window.addEventListener("load",function()
{
    new Effect.Morph('completed', {
        style: 'width:{/literal}{$done}{literal}%',
        duration: 4.0
    });
});

var accord_dashboard = new accordion('block_dashboard');

function activateAccordeon(theAccord){

    accord_dashboard.activate($$('#block_dashboard .accordion_toggle')[theAccord]);
    setCookie("activeSlideProject",theAccord);
}

//var theBlocks = $$("#block_dashboard > div .headline > a");
var theBlocks = document.querySelectorAll("#block_dashboard > div .headline > a");
console.log(theBlocks);

//loop through the blocks and add the accordion toggle link
openSlide = 0;
for(i=0;i<theBlocks.length;i++)
{
    theCook = readCookie("activeSlideProject");
    console.log(theCook);
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