<template>
    <ul class="chat">
        <li class="left clearfix" v-for="(onlineUser, itemIndex) in chatUsers">
            <div class="chat-body clearfix">
                <div
                   style="2px solid white;cursor:pointer"
                   :class="{'selectedUser': activeItemId === itemIndex}"
                   @click="privateUser(onlineUser), setActiveItemId(itemIndex)" class="header"
                >
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
      data: function () {
        return {
         activeItemId: ''
        }
      },


      methods: {
           privateUser(user) {
               this.$emit('touser', {
                    data: user,
               });
           },
           setActiveItemId(itemIndex) {
               this.activeItemId = itemIndex;
           }
      },

      mounted () {
            // Do something useful with the data in the template
            //console.dir(this.test);
        }
  };
</script>