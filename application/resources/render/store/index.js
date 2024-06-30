import user from './modules/user';

const debug = process.env.NODE_ENV !== 'production';

export default {
  modules: {
      user
  },
  strict: debug,
  plugins: []
};
