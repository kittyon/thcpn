<template>
  <div>
    <el-tabs v-model="activeName" type="card" style="margin-top:20px">
      <el-tab-pane v-for="(image,key) in images" :label="image.name" :name="key" :key="key">
        <el-card v-for="(iis, ts) in image.data" class="box-card" :key="ts">
          <div slot="header" class="clearfix">
            <span>{{ts}}</span>
          </div>
          <el-row>
            <el-col :span="6" v-for="(i,index) in iis.images" :key="i.ts" :offset="1">
              <el-card :body-style="{ padding: '0px' }" style="margin-top: 5px">
                <img class="image" v-img:group :src="'http://thc-platfrom-storage.b0.upaiyun.com'+i.value" alt="alt"/>
                <div style="padding: 8px;">
                  <div class="bottom clearfix">
                    <time class="time">{{ i.ts }}</time>
                    <el-button class="button" type="text"><i class="fa fa-download"></i></el-button>
                  </div>
                </div>
              </el-card>
            </el-col>
          </el-row>
        </el-card>
      </el-tab-pane>
    </el-tabs>

  </div>
</template>

<script>
import api from '../../../api';

export default {
  name: 'device-picture',
  props:['images'],
  data(){
    return {
      activeName:"",
    }
  },
  created: function(){
    this.activeName = _.keys(this.images)[0];
  },
  methods:{
  }
}
</script>
<style>
.time {
    font-size: 13px;
    color: #999;
  }

  .bottom {
    margin-top: 13px;
    line-height: 12px;
  }

  .button {
    padding: 0;
    float: right;
  }

  .image {
    width: 100%;
    display: block;
  }

  .clearfix:before,
  .clearfix:after {
      display: table;
      content: "";
  }

  .clearfix:after {
      clear: both
  }
</style>
