const sponsorController = {
    index: async (req, res) => {
        try {
            res.render('pages/sponsors', {
                title: 'Sponsors - YouthConnekt Sahel 2025',
                page: 'sponsors'
            });
        } catch (error) {
            console.error('Erreur:', error);
            res.render('pages/sponsors', {
                title: 'Sponsors - YouthConnekt Sahel 2025',
                page: 'sponsors'
            });
        }
    }
};

module.exports = sponsorController;