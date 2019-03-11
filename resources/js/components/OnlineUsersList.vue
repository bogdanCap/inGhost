<template>
    <ul class="chat">
        <li class="left clearfix" v-for="onlineUser in chatUsers">
            <div class="chat-body clearfix">
                <div style="2px solid white;cursor:pointer"  @click="privateUser(onlineUser)" class="header">
                    <strong class="primary-font">
                        {{ onlineUser.name }}
                    </strong>
                </div>

            </div>
        </li>
    </ul>
</template>

<script>
  export default {
      props: ['chatUsers'],


      methods: {
           privateUser(user) {
               this.$emit('touser', {
                    data: user,
               });

           },
      },

      mounted () {
            // Do something useful with the data in the template
            //console.dir(this.test);
        }
  };
</script>