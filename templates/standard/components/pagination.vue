pagination
["view", "pages", "currentPage"]
<template v-if='pages.length > 1'>
    <span id="paging" style="margin-left:10px;">
        <button style="float:none;font-size:9pt;margin:0 1px 0 1px;" onclick="pagination.loadPrevPage({{view}})"><<</button>

        <span id="page{{page.index}}" v-for="page in pages" style="margin-left:2px;">
           <button v-bind:class="currentPage == page.index ? 'paginationActive' : 'paginationInactive'"
                    onclick="pagination.loadPage({{view}},{{page.index}});">
                {{page.index}}
           </button>
        </span>

        <button style="float:none;font-size:9pt;margin:0 1px 0 1px;" onclick="pagination.loadNextPage({{view}})">>></button>
    </span>
</template>