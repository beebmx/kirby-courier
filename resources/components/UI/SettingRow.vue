<script setup>
  defineProps({
    field: String,
    value: String,
  })
</script>

<template>
  <tr class="k-courier-settings-row">
    <td>
      {{ field }}
      <dl>
        <dt class="sr-only">{{ field }}</dt>
        <dd>
          <code>{{ value }}</code>
        </dd>
      </dl>
    </td>

    <td>
      <code>{{ value }}</code>
    </td>
  </tr>
</template>

<style scoped>
  .k-courier-settings-row:where(:not(:last-child)) td {
    border-bottom: 1px solid light-dark(var(--color-gray-250), var(--color-gray-900));
  }
  .k-courier-settings-row:hover td {
    background: light-dark(var(--color-gray-150), var(--color-gray-800));
  }
  .k-courier-settings-row td {
    padding: var(--spacing-3);
  }
  .k-courier-settings-row td:last-child {
    display: none;
  }
  .k-courier-settings-row dl {
    margin-top: var(--spacing-3);
  }
  .k-courier-settings-row code {
    background: light-dark(var(--color-gray-100), var(--color-gray-950));
    border-radius: var(--rounded-md);
    font-family: var(--font-mono);
    font-size: var(--text-xs);
    overflow: hidden;
    padding: var(--spacing-1);
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  @media (min-width: 60rem) {
    .k-courier-settings-row dl {
      display: none;
    }
    .k-courier-settings-row td:first-child {
      width: 25%;
    }
    .k-courier-settings-row td:last-child {
      display: table-cell;
    }
  }
</style>
