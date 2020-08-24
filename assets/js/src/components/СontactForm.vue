<template>
    <form action="">
        <div class="form-group" :class="{ 'input--error': form.submitted && $v.form.name.$error }">
            <input v-model="form.name" aria-label="Имя" type="text" placeholder="Имя*" class="join_input gtzm">
            <div class="error" v-if="form.submitted && !$v.form.name.required">Имя обязательно</div>
        </div>

        <div class="form-group">
            <input v-model="form.email" aria-label="E-mail" type="text" placeholder="E-mail" class="join_input gtzm">
        </div>

        <div class="form-group" :class="{ 'input--error': form.submitted && $v.form.phone.$error }">
            <masked-input class="join_input gtzm" type="phone" name="phone" v-model.trim="$v.form.phone.$model" placeholder="Номер телефона*" mask="\+\7 (111) 111-11-11" @focus.native="focusPhone = true"></masked-input>
            <div class="error" v-if="focusPhone && !$v.form.phone.required">Нужен телефон</div>
            <div class="error" v-if="focusPhone && !$v.form.phone.correctPhone">Должен быть действующий телефон</div>
        </div>

        <div class="form-group">
            <textarea v-model="form.message" aria-label="Сообщение" type="text" placeholder="Сообщение" class="join_input gtzm"></textarea>
        </div>

        <div class="join-form-buttons">
            <button 
                class="join-form-button button-submit gtzm state-button" @click.prevent="submitContactForm" 
				:class="{ 
					'state-button--pending': form.submitStatus == 'PENDING', 
					'state-button--success': form.submitStatus == 'SUCCESS',
					'state-button--fail': form.submitStatus == 'ERROR',
				}">
				<span class="join-form-button__text state-button__text">Отправить</span>
			</button>
        </div> 
    </form>
</template>    

<script>
import { required, email, minLength } from "vuelidate/lib/validators";
export default {
    props: {
        locale: {
            type: String,
        },
    },
    data() {
        return {
            template_url: SITEDATA.themepath,
            focusPhone: false,
            form: {
                submitted: false,
                submitStatus: '',
                name: "",
                email: "",
                phone: "",
                message: "",
            }
        };
    },
    validations: {
        form: {
            name: {
                required
            },
            phone: {
                required,
                correctPhone: (phone) => {
                    return phone.replace(/[^\d\.]/g, '').length == 11
                },
            }
        }
    },
    methods: {
        clearForm(){
            this.form.submitted =  false;
            this.form.name =  "";
            this.form.email =  "";
            this.form.phone =  "";
            this.form.message =  "";
        },
        showModal: (modalName) => {
            const currentModal = document.querySelector(`.${modalName}`);
            const overlay = document.querySelector('.overlay');
            if (currentModal) {
                currentModal.classList.add('modal--show');
                overlay.classList.add('overlay--show');
            }
        },
        async submitContactForm(){
            this.form.submitted = true;
            this.$v.form.$touch()
            this.focusPhone = true
 
            let formReg = new FormData(); 
            formReg.append("name", this.form.name);
            formReg.append("email", this.form.email);
            formReg.append("phone", this.form.phone);
            formReg.append("message", this.form.message);

            let fetchData = {
                method: "POST",
                body: formReg
            };
            
            if (this.$v.form.$invalid) {
                this.form.submitStatus = 'ERROR';
                setTimeout(() => {this.form.submitStatus = ''}, 1000);
            } else {
                this.form.submitStatus = 'PENDING'
                const sendURL = `${SITEDATA.themepath}/email-send.php`;
                let response = await fetch(sendURL, fetchData);
                let responseData = await response.json();
                if(responseData.status == 'success'){
                    this.form.submitStatus = 'SUCCESS';
                    this.clearForm();
                    setTimeout(() => {this.form.submitStatus = ''}, 1000);
                    setTimeout(() => {this.showModal('modal-window--thank')}, 1500);
                }
                else{
                    this.form.submitStatus = 'ERROR';
                    setTimeout(() => {this.form.submitStatus = ''}, 1000);
                }
            }
        },
    }
};
</script>