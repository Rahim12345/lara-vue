<template>
    <div class="m-2">
        <table class="table">
        <thead>
            <tr>
                <th>AD</th>
                <th>SOYAD</th>
                <th>ATA ADI</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="employee in employers" :data-id="employee.employee_id">
                <td class="pointer">
                    <span class="nameText" :data-id="employee.employee_id" @click="hideOrShower('text','name',employee.employee_id)">{{ employee.name }}</span>
                    <span class="nameInput" :data-id="employee.employee_id" >
                        <input type="text" class="nameInputInside form-control" :data-id="employee.employee_id" @keyup.enter="updateEmployer(employee.employee_id,'ad',value='','name','nameText')" @focusout="hideOrShower('input','name',employee.employee_id)">
                    </span>
                </td>
                <td class="pointer">{{ employee.lastname }}</td>
                <td class="pointer">{{ employee.fatherName }}</td>
                <td>
                    <button class="btn btn-danger" @click="removeEmployer(employee.employee_id)"><i class="fa fa-times"></i></button>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th><input type="text" class="form-control" v-model="name"></th>
                <th><input type="text" class="form-control" v-model="lastname"></th>
                <th><input type="text" class="form-control" v-model="fatherName"></th>
                <th><button class="btn btn-primary" @click="addEmployer"><i class="fa fa-plus"></i></button></th>
            </tr>
            <tr class="d-none" id="errorRow">
                <th><small class="text-danger" id="name-error"></small></th>
                <th><small class="text-danger" id="lastname-error"></small></th>
                <th><small class="text-danger" id="fatherName-error"></small></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    </div>
</template>

<script>
export default {
    name: "EmployeeComponent",
    data(){
        return{
            employers   : [],
            name        : '',
            lastname    : '',
            fatherName  : '',
        }
    },
    methods: {
        getEmployers(){
            let vm = this;
            axios
                .get('/api/employee')
                .then(function (response) {
                    vm.employers = response.data;
                });
        },
        addEmployer(){
            let vm = this;

            $('#errorRow').addClass('d-none');
            $('#name-error, #lastname-error, #fatherName-error').html('');
            axios
                .post('/api/employee',{
                    name        : vm.name,
                    lastname    : vm.lastname,
                    fatherName  : vm.fatherName
                })
                .then(function (response) {
                    vm.name         = '';
                    vm.lastname     = '';
                    vm.fatherName   = '';
                    toastr.success(response.data.message);
                    vm.employers.push(response.data.employer);
                })
                .catch(function (myErrors) {
                    $.each(myErrors.response.data.errors, function (key, value) {
                        $('#'+key+'-error').html(value);
                    });
                    $('#errorRow').removeClass('d-none');
                })
        },
        removeEmployer(id){
            if(confirm("Do you really want to delete?")){
                axios
                    .delete('api/employee/'+id)
                    .then(function (response) {
                        $('tr[data-id="'+id+'"]').remove();
                        toastr.success(response.data.message);
                    })
                    .catch(function (myErrors) {
                        $.each(myErrors.response.data.errors, function (key, value) {
                            toastr.error(value);
                        })
                    })
            }
        },
        updateEmployer(id,column,value,name,spanClass){
            let vm = this;
            vm.value = $('input.'+name+'InputInside[data-id="'+id+'"]').val();
            axios
                .put('api/employee/'+id,{
                    column  : column,
                    value   : vm.value,
                })
                .then(function (response) {
                    toastr.success(response.data.message);
                    $('span.'+name+'Text[data-id="'+id+'"]').html(value);
                    this.hideOrShower('input', 'name', id);
                })
                .catch(function (myErrors) {
                    $.each(myErrors.response.data.errors, function (key, value) {
                        toastr.error(value);
                    })
                })
        },
        hideOrShower(action, name, id){
            if (action == 'text')
            {
                $('span.'+name+'Text[data-id="'+id+'"]').hide();
                $('span.'+name+'Input[data-id="'+id+'"]').show();
                $('input.'+name+'InputInside[data-id="'+id+'"]').val($('span.'+name+'Text[data-id="'+id+'"]').html()).focus();
            }

            if (action == 'input')
            {
                alert()
                $('span.'+name+'Text[data-id="'+id+'"]').show();
                $('span.'+name+'Input[data-id="'+id+'"]').hide();
            }
        }
    },
    beforeMount() {
        this.getEmployers();
    }
}
</script>

<style scoped>
.pointer{
    cursor: pointer;
}

.nameInput{
    display: none;
}
</style>
