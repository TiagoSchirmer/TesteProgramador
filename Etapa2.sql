select dept_name as Nome_Departamento, 
concat(first_name,' ',last_name) as Nome_Completo, 
datediff(ifnull(dept_emp.to_date,now()), dept_emp.from_date) as Dias_Trabalhados 
from employees 
inner join dept_emp on dept_emp.emp_no = employees.emp_no
inner join departments on departments.dept_no = dept_emp.dept_no
order by Dias_Trabalhados desc
limit 10
