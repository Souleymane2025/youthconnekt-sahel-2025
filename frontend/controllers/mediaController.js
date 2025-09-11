const mediaController = {
    index: async (req, res) => {
        try {
            res.render('pages/media', {
                title: 'Média - YouthConnekt Sahel 2025',
                page: 'media'
            });
        } catch (error) {
            console.error('Erreur:', error);
            res.render('pages/media', {
                title: 'Média - YouthConnekt Sahel 2025',
                page: 'media'
            });
        }
    }
};

module.exports = mediaController;