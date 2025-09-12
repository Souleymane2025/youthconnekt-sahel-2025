const exhibitionController = {
    index: async (req, res) => {
        try {
            res.render('pages/exhibitions', {
                title: 'Expositions - YouthConnekt Sahel 2025',
                exhibitions: [],
                page: 'exhibitions'
            });
        } catch (error) {
            console.error('Erreur:', error);
            res.render('pages/exhibitions', {
                title: 'Expositions - YouthConnekt Sahel 2025',
                exhibitions: [],
                page: 'exhibitions'
            });
        }
    }
};

module.exports = exhibitionController;