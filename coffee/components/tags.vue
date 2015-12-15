<template>
	<div class="label horizontal tiny ui blue">
		<i class="icon warning"></i>
		{{machine.NAME}}
	</div>
</template>

<script>
	export default {
		props:['machine']
	}
</script>