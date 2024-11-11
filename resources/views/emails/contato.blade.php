<!DOCTYPE html>
<html>
<body>
    <h2>Nova mensagem de contato</h2>
    <p><strong>Nome:</strong> {{ $dados['name'] }}</p>
    <p><strong>E-mail:</strong> {{ $dados['email'] }}</p>
    <p><strong>Telefone:</strong> {{ $dados['phone'] ?? 'Não informado' }}</p>
    <p><strong>Cidade:</strong> {{ $dados['city'] ?? 'Não informado' }}</p>
    <p><strong>Departamento:</strong> {{ $dados['department'] }}</p>
    <p><strong>Mensagem:</strong></p>
    <p>{{ $dados['message'] }}</p>
</body>
</html> 