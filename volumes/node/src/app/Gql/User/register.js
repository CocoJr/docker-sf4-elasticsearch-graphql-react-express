/*
 * @copyright 2018 Thibault Colette
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 */

import gql from "graphql-tag";
import {ErrorFragment} from '../Fragment/error';

export default gql`
  mutation userRegister($username: String!, $email: String!, $password: String!, $passwordConfirm: String!, $termsAccepted: Boolean!) {
    userRegister(input: {username: $username, email: $email, password: $password, passwordConfirm: $passwordConfirm, termsAccepted: $termsAccepted}) {
      user {
        username
      }
      errors {
        ...ErrorFragment
      }
    }
  }
  ${ErrorFragment}
`;
