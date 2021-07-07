<p>Exibindo resultado para consulta: <span class="font-weight-bold"> {{returnCourses}} </span></p>


{% for user in returnCourses %}
<li>{{ user.name}} - {{ user.id }}</li>
{% endfor %}