<template>
	<div class="label horizontal tiny ui blue">
		{{machine.NAME}}
		<!-- <i class="icon warning"></i> -->
		<div class="detail">5</div>
	</div>
</template>

<script>
	export default {
		props:['machine']
	}
</script>