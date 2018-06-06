<!--https://github.com/PanJiaChen/vue-element-admin/blob/master/src/components/ScrollPane/index.vue-->
<template>

  <div class="scroll-container" ref="scrollContainer" @wheel.prevent="handleScroll">

    <div class="scroll-wrapper" ref="scrollWrapper" :style="{left: left + 'px'}">
      <slot></slot>
    </div>
    <span class="direction" style="left: 0" @click="left -= 76"><i class="el-icon-caret-left" ></i></span>
    <span class="direction" style="right: 0" @click="left += 76"><i class="el-icon-caret-right" ></i></span>
  </div>
</template>

<script>
    const padding = 15 ;// tag's padding
    export default {
        name: 'scrollPane',
        data() {
            return {
                left: 0
            }
        },
        methods: {
            handleScroll(e) {
                console.log(e.wheelDelta +'---'+ -e.deltaY);
                const eventDelta = e.wheelDelta || -e.deltaY * 3
                const $container = this.$refs.scrollContainer
                const $containerWidth = $container.offsetWidth
                const $wrapper = this.$refs.scrollWrapper
                const $wrapperWidth = $wrapper.offsetWidth
                if (eventDelta > 0) {
                    this.left = Math.min(0, this.left + eventDelta)
                } else {
                    if ($containerWidth - padding < $wrapperWidth) {
                        if (this.left < -($wrapperWidth - $containerWidth + padding)) {
                            this.left = this.left
                        } else {
                            this.left = Math.max(this.left + eventDelta, $containerWidth - $wrapperWidth - padding)
                        }
                    } else {
                        this.left = 0
                    }
                }
            },
            moveToTarget($target) {
                const $container = this.$refs.scrollContainer
                const $containerWidth = $container.offsetWidth
                const $targetLeft = $target.offsetLeft
                const $targetWidth = $target.offsetWidth
                if ($targetLeft < -this.left) {
                    // tag in the left
                    this.left = -$targetLeft + padding
                } else if ($targetLeft + padding > -this.left && $targetLeft + $targetWidth < -this.left + $containerWidth - padding) {
                    // tag in the current view
                    // eslint-disable-line
                } else {
                    // tag in the right
                    this.left = -($targetLeft - ($containerWidth - $targetWidth) + padding)
                }
            }
        }
    }
</script>

<style lang="less" scoped>
  .scroll-container {
    cursor: ew-resize;
    white-space: nowrap;
    position: relative;
    overflow: hidden;
    width: 100%;
    .direction{
      cursor: pointer;
      height: 100%;
      background: #fff;
      position: absolute;
      i{
        display: block;
        margin-top: -8px;
        top: 50%;
        position: relative;
      }
    }
    .scroll-wrapper {
      padding: 0 20px;
      position: absolute;
      transition: left 0.3s ease-in-out;
    }
  }
</style>