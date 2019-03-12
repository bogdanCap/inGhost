<template>
    <ul class="chat">
        <li class="" v-for="message in messages">
            <div class="chat-body clearfix">
                <div class="header">
                    <strong class="primary-font">
                        {{ message.user.name }}
                    </strong>
                </div>
                <p :class="['message', (message.parent_user_id !== null ? 'private' : ''), (message.user_id !== user.id ? 'notMy' : '')]"  >
                    {{ message.message }}
                </p>

            </div>
        </li>
    </ul>
</template>

<script>
  export default {
    props: ['messages', 'user'],

  };
</script>