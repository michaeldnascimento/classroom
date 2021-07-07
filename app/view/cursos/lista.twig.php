<h1>olá lista</h1>


<p>Exibindo resultado para lista: <span class="font-weight-bold"> {{consultList}} </span></p>


{% for user in consultList %}
<li>{{ user.name}} - {{ user.id }}</li>
{% endfor %}


