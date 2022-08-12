<template>
    <div class="toggle_button button r">
        <input :name="p_name" :id="p_id" type="checkbox" class="checkbox" v-model="is_checked"/>
        <div class="knobs"></div>
        <div class="layer"></div>
    </div>
</template>

<script>
export default{
    props: ['prop_checked', 'p_name', 'p_id'],
    data: function(){
        return{
            is_checked: this.prop_checked ?? false
        }
    },
    methods: {
        
    }
}
</script>

<style>
.knobs,
.layer {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}

.button {
  position: relative;
  width: 50px;
  height: 28px;
  overflow: hidden;
}

.button.r,
.button.r .layer {
  border-radius: 50px;
}

.checkbox {
  position: relative;
  width: 100%;
  height: 100%;
  padding: 0;
  margin: 0;
  opacity: 0;
  cursor: pointer;
  z-index: 3;
}

.knobs {
  z-index: 2;
}

.layer {
  background-color: #de9e92;
  transition: 0.3s ease all;
  z-index: 1;
}

.toggle_button .knobs:before {
  content: "";
  position: absolute;
  top: 4px;
  left: 4px;
  width: 21px;
  height: 21px;
  background-color: #e32e0e;
  border-radius: 50%;
  transition: 0.3s ease all, left 0.3s cubic-bezier(0.18, 0.89, 0.35, 1.15);
}

.toggle_button .checkbox:active + .knobs:before {
  width: 46px;
  border-radius: 100px;
}

.toggle_button .checkbox:checked:active + .knobs:before {
  margin-left: -26px;
}

.toggle_button .checkbox:checked + .knobs:before {
  content: "";
  left: 25px;
  background-color: #36b9cc;
}

.toggle_button .checkbox:checked ~ .layer {
  background-color: #9bc6cc;
}
</style>