<script setup>
  import { computed, ref } from 'vue'

  const props = defineProps({
    user: Object,
  })

  const user = computed(() => props.user)
  const email = ref(user.value?.email || '')

  function send() {
    if (!email.value) {
      window.panel.notification.error(window.panel.$t('field.required'))
      return
    }
    window.panel.api
      .post('courier/email/test', {
        email: email.value,
      })
      .then(({ success, message }) => {
        if (success) {
          window.panel.notification.success(message)
        } else {
          window.panel.notification.error(message)
        }
      })
      .catch((error) => {})
      .finally(() => {})
  }
</script>

<template>
  <k-section class="k-courier-email-sender">
    <k-input type="email" name="email" icon="email" :value="email" @input="email = $event" @submit="send" />

    <k-button variant="filled" size="lg" @click="send">{{ $t('beebmx.courier.panel.email.test') }}</k-button>
  </k-section>
</template>

<style scoped>
  .k-courier-email-sender {
    align-items: center;
    background: light-dark(var(--color-gray-100), var(--color-gray-800));
    border-radius: var(--rounded-lg);
    box-shadow: var(--shadow-sm);
    display: flex;
    gap: var(--spacing-2);
    padding: var(--spacing-2);
  }
  .k-courier-email-sender .k-input {
    flex: 1;
  }
</style>
