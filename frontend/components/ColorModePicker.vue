<template>
  <div
    class="d-inline-block me-0 me-md-2 tooltipBtn"
    data-bs-toggle="tooltip"
    data-bs-placement="bottom"
    data-bs-custom-class="color-theme-tooltip"
    :title="$colorMode.preference"
  >
    <div @click="changeTheme">
      <IconSystem v-if="$colorMode.preference === 'system'" />
      <IconLight v-if="$colorMode.preference === 'light'" />
      <IconDark v-if="$colorMode.preference === 'dark'" />
      <IconSepia v-if="$colorMode.preference === 'sepia'" />
    </div>
  </div>
</template>

<script>
import IconSystem from '@/assets/icons/system.svg?inline'
import IconLight from '@/assets/icons/light.svg?inline'
import IconDark from '@/assets/icons/dark.svg?inline'
import IconSepia from '@/assets/icons/sepia.svg?inline'
export default {
  name: 'ColorModePicker',
  components: {
    IconSystem,
    IconLight,
    IconDark,
    IconSepia,
  },
  methods: {
    changeTheme() {
      if (this.$colorMode.preference === 'system') {
        this.$colorMode.preference = 'light'
      } else if (this.$colorMode.preference === 'light') {
        this.$colorMode.preference = 'dark'
      } else if (this.$colorMode.preference === 'dark') {
        this.$colorMode.preference = 'sepia'
      } else if (this.$colorMode.preference === 'sepia') {
        this.$colorMode.preference = 'system'
      }
    },
  },
}
</script>

<style scoped>
.feather {
  position: relative;
  top: 0px;
  height: auto;
  width: 1.5rem;
  cursor: pointer;
  padding: 4px;
  background-color: var(--bg-secondary);
  border: 2px solid var(--border-color);
  margin: 0;
  border-radius: 5px;
  transition: all 0.1s ease;
}
.feather:hover {
  top: -3px;
}
.feather.preferred {
  border-color: var(--color-primary);
  top: -3px;
}
.feather.selected {
  color: var(--color-primary);
}
</style>
