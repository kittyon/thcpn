<template>
  <div>
    <!--header-->
    <div class="sub-navbar draft">
      <el-button @click="handleAdd">{{$t('table.create')}}</el-button>
    </div>
    <device-list :org="org"></device-list>

    <el-dialog :title="$t('device.attach')" :visible.sync="dialogAttachVisible">
      <el-input v-model="deviceIccid" :tooltip="$t('device.attachDescription')">
        <template slot="prepend">iccid</template>
      </el-input>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogAttachVisible = false">{{$t('table.cancel')}}</el-button>
        <el-button type="primary" @click="attachDevice">{{$t('table.confirm')}}</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import DeviceList from "../device/list.vue"

export default{
  props:['org'],
  components:{
    'device-list':DeviceList
  },
  data(){
    return {
      dialogAttachVisible: false,
      deviceIccid:""
    }
  },
  methods:{
    handleAdd: function(){

    },
    attachDevice: function(){

    }
  }

}
</script>
