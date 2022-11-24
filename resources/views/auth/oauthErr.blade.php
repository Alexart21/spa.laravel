<style>
    .err{
        padding: 2em;
    }
</style>
<div class="err">
    <h2>Вы уже регистрировались используя email <span style="color:blue">{{ $email }}</span></h2>
    <h3>Используйте {{ $email }} для входа или воспользуйтесь сервисом где у Вас email отличный от <span style="color:blue">{{ $email }}</span></h3>
    <br>
    @include('auth.oauth-icons')
</div>
