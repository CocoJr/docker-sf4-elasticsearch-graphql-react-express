import gql from "graphql-tag";
import {ErrorFragment} from '../Fragment/error';

export default gql`
  mutation userLogin($username: String!, $password: String!) {
    userLogin(input: {username: $username, password: $password}) {
      token {
        id
        value
      }
      errors {
        ...ErrorFragment
      }
    }
  }
  ${ErrorFragment}
`;
