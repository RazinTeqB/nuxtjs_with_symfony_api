<template>
  <div class="notification text-danger">
    {{ message }}
  </div>
</template>

<script>
export default {
  name: 'Notification',
  props: {
    message: {
      type: String,
      required: true,
    },
  },
}
</script>
