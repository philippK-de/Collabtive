var loaderComponent = Vue.extend({
	props: ["block", "loader"],
	template: "<div class=\"progress float-left display-none\" v-bind:id=\"'progress' + block\"><img v-bind:src=\"'templates\/standard\/theme\/standard\/images\/symbols\/' + loader\" \/><\/div>"
});

Vue.component("loader", loaderComponent);