<template>
    <a-modal title="图表" width="60%">
        <div ref="pieChart" style="width: 600px; height:900px;margin: auto;"> </div>
        <div ref="barChart" style="width: 600px; height:900px;margin: auto;"> </div>
        <template #footer>
        </template>
    </a-modal>
</template>
<script setup lang="ts">
import * as echarts from 'echarts';
import { ref, onUpdated, onMounted, onBeforeUpdate } from 'vue'
import { getStudentList } from '~/server/student/index.ts'
import { isRequestSuccess } from '~/server';

const pieChart = ref<any>(null);
const barChart = ref<any>(null);
const data = ref<{
    value: number,
    name: string
}[]>([]);
const option = {
    tooltip: {
        trigger: 'item'
    },
    legend: {
        top: '5%',
        left: 'center'
    },
    toolbox: {
        show: true,
        feature: {
            dataZoom: {
                yAxisIndex: "none"
            },
            dataView: {
                readOnly: true
            },
            magicType: {
                type: ["line", "bar"]
            },
            restore: {},
            saveAsImage: {}
        }
    },
    series: [
        {
            name: '学生姓名',
            type: 'pie',
            radius: ['40%', '70%'],
            avoidLabelOverlap: false,
            itemStyle: {
                borderRadius: 10,
                borderColor: '#fff',
                borderWidth: 2
            },
            label: {
                show: false,
                position: 'center'
            },
            emphasis: {
                label: {
                    show: true,
                    fontSize: 40,
                    fontWeight: 'bold'
                }
            },
            labelLine: {
                show: false
            },
            data: data.value
        }
    ]
};


const barSeriesData = ref<string[]>([]);
const barYaxisData = ref<string[]>([]);
const barOption = {
    xAxis: {
        max: 'dataMax'
    },
    yAxis: {
        type: 'category',
        data: barYaxisData.value,
        inverse: true,
        animationDuration: 300,
        animationDurationUpdate: 300,
    },
    toolbox: {
        show: true,
        feature: {
            dataZoom: {
                yAxisIndex: "none"
            },
            dataView: {
                readOnly: true
            },
            magicType: {
                type: ["line", "bar"]
            },
            restore: {},
            saveAsImage: {}
        }
    },
    series: [
        {
            realtimeSort: true,
            name: '记分',
            type: 'bar',
            data: barSeriesData.value,
            label: {
                show: true,
                position: 'right',
                valueAnimation: true
            }
        }
    ],
    legend: {
        show: true
    },
    animationDuration: 0,
    animationDurationUpdate: 3000,
    animationEasing: 'linear',
    animationEasingUpdate: 'linear'
};

const chart = ref<any>();
const barchart = ref<any>();
const getStudentListFetch = (): void => {
    getStudentList.fetch({ isAll: true }).then((res: getStudentList.returnResponse): void => {
        if (isRequestSuccess(res)) {
            if (data.value.length == res.data.count) return
            res.data.list.forEach((item: getStudentList.Data): void => {
                data.value.push({
                    value: item.student_score_count,
                    name: item.student_name,
                })
                barYaxisData.value.push(item.student_name)
                barSeriesData.value.push(item.student_score_count)
            });
            chart.value = echarts.init(pieChart.value)
            chart.value.setOption(option);
            barchart.value = echarts.init(barChart.value)
            barchart.value.setOption(barOption)
        }
    })
}

onUpdated(() => {
    getStudentListFetch()

})


</script>
<style scoped lang="less"></style>