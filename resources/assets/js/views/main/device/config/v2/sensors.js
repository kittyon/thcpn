export default {
    "davis_rain": {
        "desc": "降雨传感器",
        "port": "PI",
        "port_nums": [0, 1],
        "data_nums": [
            {
                "data_num": 0,
                "unit": "mm",
                "type": "rain",
                "desc": "降雨量"
            }
        ]
    },
    "mec10": {
        "desc": "土壤传感器",
        "port": "485",
        "port_nums": [0, 1],
        "data_nums": [
            {
                "data_num": 0,
                "unit": "℃",
                "type": "temperature",
                "desc": "土壤温度"
            },
            {
                "data_num": 1,
                "unit": "%",
                "type": "humdity",
                "desc": "土壤湿度"
            }
        ]
    },
    "nh122": {
        "desc": "环境传感器",
        "port": "485",
        "port_nums": [0, 1],
        "data_nums": [
            {
                "data_num": 0,
                "unit": "℃",
                "type": "temperature",
                "desc": "空气温度"
            },
            {
                "data_num": 1,
                "unit": "%",
                "type": "humdity",
                "desc": "空气湿度"
            },
            {
                "data_num": 2,
                "unit": "hPa",
                "type": "pressure",
                "desc": "气压"
            }
        ]
    },
    "nhfs45bu": {
        "desc": "风速传感器",
        "port": "AD",
        "port_nums": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
        "data_nums": [
            {
                "data_num": 0,
                "unit": "m/s",
                "type": "windspeed",
                "desc": "风速"
            }
        ]
    },
    "nhfx46au": {
        "desc": "风向传感器",
        "port": "AD",
        "port_nums": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
        "data_nums": [
            {
                "data_num": 0,
                "unit": "°",
                "type": "winddirect",
                "desc": "风向"
            }
        ]
    },
    "nhzd10": {
        "desc": "光照传感器",
        "port": "AD",
        "port_nums": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
        "data_nums": [
            {
                "data_num": 0,
                "unit": "lux",
                "type": "solar",
                "desc": "光照"
            }
        ]
    },
    "th10s": {
        "desc": "土壤传感器",
        "port": "485",
        "port_nums": [0, 1],
        "data_nums": [
            {
                "data_num": 0,
                "unit": "℃",
                "type": "temperature",
                "desc": "土壤温度"
            },
            {
                "data_num": 1,
                "unit": "%",
                "type": "humdity",
                "desc": "土壤湿度"
            }
        ]
    },
    "ms10vt": {
        "desc": "土壤传感器",
        "port": "AD",
        "port_nums": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
        "data_nums": [
            {
                "data_num": 0,
                "unit": "℃",
                "type": "temperature",
                "desc": "土壤温度"
            }
        ]
    },
    "ms10vh": {
        "desc": "土壤传感器",
        "port": "AD",
        "port_nums": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
        "data_nums": [
            {
                "data_num": 0,
                "unit": "%",
                "type": "humdity",
                "desc": "土壤湿度"
            }
        ]
    },
    "wxt520": {
        "desc": "环境传感器",
        "port": "485",
        "port_nums": [0, 1],
        "data_nums": [
            {
                "data_num": 0,
                "unit": "m/s",
                "type": "windspeed",
                "desc": "风速"
            },
            {
                "data_num": 1,
                "unit": "°",
                "type": "winddirect",
                "desc": "风向"
            },
            {
                "data_num": 2,
                "unit": "℃",
                "type": "temperature",
                "desc": "空气温度"
            },
            {
                "data_num": 3,
                "unit": "%",
                "type": "humdity",
                "desc": "空气湿度"
            },
            {
                "data_num": 4,
                "unit": "hPa",
                "type": "pressure",
                "desc": "气压"
            }
        ]
    },
    "hisi": {
        "desc": "图像传感器",
        "port": "Img",
        "port_nums": [0, 1],
        "data_nums": [
            {
                "data_num": 0,
                "unit": "",
                "type": "image",
                "desc": "图像"
            }
        ]
    }
}
