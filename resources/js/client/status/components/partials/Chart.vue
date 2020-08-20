<script>
    import { Line } from 'vue-chartjs'

    export default {
        extends: Line,
        name: "Chart",
        props: ['chartData', 'options'],
        mounted () {
            this.renderChart(this.chartData, this.options)
        },
		watch: {
			chartData() {
				this.$data._chart.update()
			}
		}
    }
</script>
