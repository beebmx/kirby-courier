<script setup>
  import { computed, ref } from 'vue'

  const props = defineProps({
    user: Object,
  })

  const user = computed(() => props.user)
  const email = ref(user.value?.email || '')

  const isSending = ref(false)

  async function send() {
    if (!email.value) {
      window.panel.notification.error(window.panel.t('field.required'))
      return
    }

    isSending.value = true

    try {
      const { success, message } = await window.panel.api.post('courier/email/test', {
        email: email.value,
      })

      success ? window.panel.notification.success(message) : window.panel.notification.error(message)
    } catch (error) {
      window.panel.error(error)
    } finally {
      isSending.value = false
    }
  }
</script>

<template>
  <k-section class="k-courier-email-sender">
    <k-input type="email" name="email" icon="email" :value="email" @input="email = $event" @submit="send" />

    <k-button variant="filled" size="lg" :icon="isSending ? 'loader' : 'email'" :disabled="isSending" @click="send">
      {{ $t('beebmx.courier.panel.email.test') }}
    </k-button>
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
