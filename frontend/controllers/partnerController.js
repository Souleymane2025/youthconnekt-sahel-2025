const partnerController = {
    index: async (req, res) => {
        try {
            res.render('pages/partners', {
                title: 'Partenaires - YouthConnekt Sahel 2025',
                page: 'partners'
            });
        } catch (error) {
            console.error('Erreur:', error);
            res.render('pages/partners', {
                title: 'Partenaires - YouthConnekt Sahel 2025',
                page: 'partners'
            });
        }
    }
};

module.exports = partnerController;