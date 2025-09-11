function getStore(req){
    return (req.app && req.app.locals._blogsStore) ? req.app.locals._blogsStore : [];
}

const blogController = {
    index: async (req, res) => {
        try {
            const blogs = getStore(req).slice().sort((a,b)=> new Date(b.createdAt)-new Date(a.createdAt));
            res.render('pages/blog', {
                title: 'Blog - YouthConnekt Sahel 2025',
                blogs,
                page: 'blog',
                currentPage: 1
            });
        } catch (error) {
            console.error('Erreur:', error);
            res.render('pages/blog', { title: 'Blog - YouthConnekt Sahel 2025', blogs: [], page: 'blog', currentPage: 1 });
        }
    },

    show: async (req, res) => {
        try {
            const slug = req.params.slug;
            const blog = getStore(req).find(b=>b.slug===slug);
            if(!blog){
                return res.status(404).render('pages/404');
            }
            res.render('pages/blog-detail', {
                title: `${blog.title} - YouthConnekt`,
                blog,
                page: 'blog'
            });
        } catch (error) {
            console.error('Erreur:', error);
            res.status(404).render('pages/404');
        }
    }
};

module.exports = blogController;