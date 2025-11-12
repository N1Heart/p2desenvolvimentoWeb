</main>

<footer>
    <p style="text-aling: center; margin-top: 20px; color: #888;">
        &copy; <?php echo date('y'); ?> Meu sistema CRUD
    </p>
</footer>

<script>
    document.querySelectorAll('.delete-link').forEach(link=> {
        link.addEventListener('click', function(event){
            if(!confirm('Tem certeza que deseja excluir esse livro?')){
                event.preventDefault();
            }
        }
    );

    })
</script>


</body>
</html>